import fs from 'node:fs';
import * as cheerio from 'cheerio';
const p = JSON.parse(fs.readFileSync('raw/pages.json')).find(x => x.slug === 'ukazka-strany');
const $ = cheerio.load(p.content.rendered);
const titles = {};
$('a[href^="#tab-"]').each((_, a) => { const id = $(a).attr('href').slice(1); if (!titles[id]) titles[id] = $(a).text().replace(/\s+/g, ' ').trim(); });
const groups = [];
$('.fusion-tabs').each((_, tabs) => {
  const heading = $(tabs).closest('.fusion-column-wrapper, .fusion-builder-row').find('h1').first().text().replace(/\s+/g, ' ').trim();
  const g = { title: heading, tabs: [] };
  $(tabs).find('.tab-pane').each((_, pane) => {
    const id = $(pane).attr('id');
    const inner = $(pane).html();
    const c = cheerio.load('<div id="r">' + inner + '</div>', null, false);
    c('style,script,ul,h4').remove();
    c('#r *').each((_, e) => { for (const k of Object.keys(e.attribs || {})) c(e).removeAttr(k); });
    let html = c('#r').html().replace(/&nbsp;/g, ' ').replace(/<\/?(span|div)>/g, '').replace(/\n\s*\n+/g, '\n').trim();
    g.tabs.push({ id: id.replace('tab-', ''), title: titles[id], html });
  });
  groups.push(g);
});
fs.writeFileSync('../content/trainings.json', JSON.stringify(groups, null, 1));
console.log(groups.map(g => g.title + ' -> ' + g.tabs.map(t => t.title).join(' | ')).join('\n'));
console.log(groups[0].tabs[0].html);

// úprava na lektorov + zoznam
groups[0].title = 'Futbalové tréningy';
groups[1].title = 'Hudba, duchovný rozvoj a mládežnícke aktivity';
for (const g of groups) for (const t of g.tabs) {
  const lines = t.html.split('\n').map(s => s.trim()).filter(s => s && s !== '.' );
  let lektori = '', out = [], list = [];
  const flush = () => { if (list.length) { out.push('<ul>' + list.map(x => `<li>${x}</li>`).join('') + '</ul>'); list = []; } };
  for (const l of lines) {
    if (/^<strong>\s*Lektori/i.test(l)) { lektori = l.replace(/<[^>]+>/g, '').replace(/^Lektori\s*[–-]\s*/, '').trim(); continue; }
    if (/^[–-]\s*/.test(l)) list.push(l.replace(/^[–-]\s*/, ''));
    else { flush(); out.push('<p>' + l + '</p>'); }
  }
  flush();
  t.lektori = lektori;
  t.html = out.join('\n').replace(/<img>/g, '').replace(/<br>/g, '').replace(/<p><p>/g, '<p>').replace(/<\/p><\/p>/g, '</p>').replace(/<p>\s*<\/p>/g, '');
}
fs.writeFileSync('../content/trainings.json', JSON.stringify(groups, null, 1));
