// node audit.mjs <cesta> <sirka> <nazov>  -> shots/<nazov>-N.png (rezy po 1300px)
import puppeteer from 'puppeteer-core';
import sharp from 'sharp';
const [path, w, name] = process.argv.slice(2);
const b = await puppeteer.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe', headless: 'new' });
const pg = await b.newPage(); await pg.setViewport({ width: +w, height: 900 });
await pg.goto('http://127.0.0.1:8080/' + path, { waitUntil: 'networkidle0' });
await pg.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 400) { window.scrollTo({ top: y, behavior: 'instant' }); await new Promise(r => setTimeout(r, 50)); } window.scrollTo({ top: 0, behavior: 'instant' }); await new Promise(r => setTimeout(r, 1500)); });
const buf = await pg.screenshot({ fullPage: true });
await b.close();
const m = await sharp(buf).metadata(); const H = 1300; let n = 0;
for (let y = 0; y < m.height; y += H) { await sharp(buf).extract({ left: 0, top: y, width: m.width, height: Math.min(H, m.height - y) }).toFile(`shots/${name}-${++n}.png`); }
console.log(name, m.width, m.height, n + ' rezov');
