// použitie: node shot.mjs <cesta> <sirka> <vystup> [vyska]  – celá stránka po prescrollovaní
import puppeteer from 'puppeteer-core';
const [path, w = 1440, out = 'shot', h = 900] = process.argv.slice(2);
const b = await puppeteer.launch({ executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe', headless: 'new' });
const pg = await b.newPage();
await pg.setViewport({ width: +w, height: +h });
const logs = [];
pg.on('console', m => logs.push(m.text()));
pg.on('pageerror', e => logs.push('ERR ' + e.message));
await pg.goto('http://127.0.0.1:8080/' + path, { waitUntil: 'networkidle0' });
await pg.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 500) { window.scrollTo({ top: y, behavior: "instant" }); await new Promise(r => setTimeout(r, 60)); } window.scrollTo({ top: 0, behavior: "instant" }); await new Promise(r => setTimeout(r, 900)); });
await pg.screenshot({ path: `shots/${out}.png`, fullPage: true });
if (logs.length) console.log(logs.join('\n'));
await b.close();
