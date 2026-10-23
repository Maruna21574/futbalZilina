import fs from 'node:fs';
import * as cheerio from 'cheerio';
const p = JSON.parse(fs.readFileSync('raw/pages.json')).find(x => x.slug === 'skuska2');
const $ = cheerio.load(p.content.rendered);
const days = $('table thead th').map((_, e) => $(e).text().trim()).get().map(d => ({ name: d, items: [] }));
$('table tbody tr').each((_, tr) => $(tr).find('td').each((i, td) => {
  const hs = $(td).find('h6'); if (!hs.length || !days[i]) return;
  const title = $(hs[0]).text().trim().replace(/\s+/g, ' ');
  const time = $(hs[1]).text().trim().replace(/\s+/g, '').replace('–', ' – ').replace('-', ' – ');
  if (!title) return;
  days[i].items.push({ title, time: $(hs[1]).text().trim().replace(/\s*[–-]\s*/, ' – '), irregular: /ff0000/i.test($(td).html()) });
}));
fs.writeFileSync('../content/schedule.json', JSON.stringify(days, null, 1));
console.log(days.map(d => d.name + ':' + d.items.length).join(' '));
// zvyšok stránky mimo tabuľky
const rest = cheerio.load(p.content.rendered); rest('table').remove();
console.log(rest.root().text().replace(/\s+/g, ' ').slice(0, 600));
