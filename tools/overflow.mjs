import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe', headless: 'new' });
const pg = await b.newPage(); await pg.setViewport({ width: +(process.env.W||390), height: 844 });
for (const p of process.argv.slice(2)) {
  await pg.goto('http://127.0.0.1:8080/' + p, { waitUntil: 'networkidle0' });
  const r = await pg.evaluate(() => { const W = document.documentElement.clientWidth; const out = [];
    document.querySelectorAll('body *').forEach(e => { const r = e.getBoundingClientRect(); if (r.right > W + 2 && r.width > 0 && getComputedStyle(e).position !== 'fixed') out.push(e.tagName + '.' + e.className + ' ' + Math.round(r.right)); });
    return { sw: document.documentElement.scrollWidth, W, out: out.slice(0, 8) }; });
  console.log(p || '/', JSON.stringify(r));
}
await b.close();
