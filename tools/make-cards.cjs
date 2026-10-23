// Dedikované obrázky pre karty tímov a hero (z plných fotiek v assets/uploads)
const s = require('./node_modules/sharp');
const jobs = [
  ['teams/pripravka.jpg', '2019/11/IMG_3902.jpg', 760, 960],
  ['teams/mladsi-ziaci.jpg', '2023/11/IMG_0574.jpg', 760, 960],
  ['teams/ziaci.jpg', '2024/05/IMG_4515.jpg', 760, 960, 'left'],
  ['teams/dorast.jpg', '2023/11/IMG_0677.jpg', 760, 960],
  ['hero.jpg', '2025/11/IGP3368.jpg', 1000, 1100],
];
(async () => {
  for (const [out, src, w, h, pos] of jobs) {
    await s('assets/uploads/' + src).resize(w, h, { fit: 'cover', position: pos || 'attention' }).jpeg({ quality: 82, mozjpeg: true }).toFile('assets/img/' + out);
  }
  const names = jobs.map(j => j[0]);
  const bufs = await Promise.all(names.map(n => s('assets/img/' + n).resize(300, 380, { fit: 'cover' }).toBuffer()));
  await s({ create: { width: 300 * 5, height: 380, channels: 3, background: '#fff' } }).composite(bufs.map((b, i) => ({ input: b, left: i * 300, top: 0 }))).jpeg().toFile('tools/shots/cards.jpg');
})();
