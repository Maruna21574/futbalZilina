// Stiahne obrázky zo starého webu, zmenší ich (full max 1600px, thumb 480px) a uloží do assets/uploads
// Spustenie: node download-images.mjs   (je idempotentné, preskakuje už stiahnuté)
import fs from 'node:fs';
import path from 'node:path';
import sharp from 'sharp';

const ROOT = path.resolve(import.meta.dirname, '..');
const OUT = path.join(ROOT, 'assets', 'uploads');
const list = JSON.parse(fs.readFileSync(path.join(import.meta.dirname, 'images.json'), 'utf8'));
const extra = [
  'https://kskzilina.sk/wp-content/uploads/2024/09/ksk_logo_farebne-01-e1726433047940.png',
  'https://kskzilina.sk/wp-content/uploads/2017/02/logo-at-toyota1.png',
  'https://kskzilina.sk/wp-content/uploads/2018/08/kardan.png',
  'https://kskzilina.sk/wp-content/uploads/2023/01/logo.100rocnica.sdb_.png',
  'https://kskzilina.sk/wp-content/uploads/2025/10/logo_kinekus_vseobecne_blue.png',
  'https://kskzilina.sk/wp-content/uploads/2025/11/zsk_logo_zsk_color.png',
  'https://kskzilina.sk/wp-content/uploads/2017/02/financna_podpora_Mesta_Zilina.jpg',
  'https://kskzilina.sk/wp-content/uploads/2017/02/dorast-16-17.jpg',
  'https://kskzilina.sk/wp-content/uploads/2017/02/ziaci-16-17-br-1.jpg',
  'https://kskzilina.sk/wp-content/uploads/2017/02/DSC_5048_1.jpg',
  'https://kskzilina.sk/wp-content/uploads/2023/04/projekt.jpg',
];
for (const u of extra) {
  const rel = u.replace(/^.*\/wp-content\/uploads\//, '');
  if (!list.find(x => x.rel === rel)) list.push({ url: u, rel, fallback: u });
}

const exists = f => fs.existsSync(f);
let done = 0, failed = [];

async function get(url) {
  const r = await fetch(url, { headers: { 'user-agent': 'Mozilla/5.0 (migracia ksk)' } });
  if (!r.ok) throw new Error(r.status + ' ' + url);
  return Buffer.from(await r.arrayBuffer());
}

async function one({ url, rel, fallback }) {
  const isPng = /\.png$/i.test(rel);
  const dest = path.join(OUT, rel);
  const thumbRel = rel.replace(/\.(png|gif)$/i, '.jpg');
  const tdest = path.join(OUT, 'thumbs', thumbRel);
  if (exists(dest) && exists(tdest)) return;
  let buf;
  try { buf = await get(url); } catch { buf = await get(fallback); }
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.mkdirSync(path.dirname(tdest), { recursive: true });
  const img = sharp(buf, { failOn: 'none' }).rotate();
  const meta = await img.metadata();
  if (isPng) {
    await sharp(buf).resize({ width: 1200, withoutEnlargement: true }).png({ compressionLevel: 9 }).toFile(dest);
  } else {
    await sharp(buf, { failOn: 'none' }).rotate().resize({ width: 1600, height: 1600, fit: 'inside', withoutEnlargement: true }).jpeg({ quality: 78, mozjpeg: true }).toFile(dest);
  }
  await sharp(buf, { failOn: 'none' }).rotate().flatten({ background: '#ffffff' }).resize({ width: 480, height: 360, fit: 'cover', position: 'attention' }).jpeg({ quality: 74, mozjpeg: true }).toFile(tdest);
}

const queue = [...list];
async function worker() {
  while (queue.length) {
    const it = queue.shift();
    try { await one(it); } catch (e) { failed.push(it.url + ' :: ' + e.message); }
    if (++done % 100 === 0) console.log(done + '/' + list.length, 'failed', failed.length);
  }
}
await Promise.all(Array.from({ length: 10 }, worker));

// súbory (PDF a pod.)
const files = JSON.parse(fs.readFileSync(path.join(import.meta.dirname, 'files.json'), 'utf8'));
for (const f of files) {
  const dest = path.join(ROOT, 'assets', 'files', f.replace(/^.*\/wp-content\/uploads\//, ''));
  if (exists(dest)) continue;
  try { fs.mkdirSync(path.dirname(dest), { recursive: true }); fs.writeFileSync(dest, await get(f)); } catch (e) { failed.push(f + ' :: ' + e.message); }
}
fs.writeFileSync(path.join(import.meta.dirname, 'failed.txt'), failed.join('\n'));
console.log('hotovo', done, 'zlyhalo', failed.length);
