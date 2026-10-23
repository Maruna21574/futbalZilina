// node sheet2.cjs <kategoria> -> kontaktný list fotiek z posledných článkov kategórie
const s=require('./node_modules/sharp'),fs=require('fs');
const cat=process.argv[2];
const posts=JSON.parse(fs.readFileSync('content/posts.json')).filter(p=>p.category===cat).slice(0,8);
let files=[];
for(const p of posts){const re=/assets\/uploads\/thumbs\/([^"]+)"/g;let m,n=0;while((m=re.exec(p.html))&&n<5){if(!files.includes(m[1])){files.push(m[1]);n++}} if(p.image&&!files.includes(p.image))files.push(p.image)}
files=files.filter(f=>fs.existsSync('assets/uploads/thumbs/'+f)).slice(0,30);
const W=300,H=225,cols=6,rows=Math.ceil(files.length/cols);
(async()=>{const comp=[];
for(let i=0;i<files.length;i++){const b=await s('assets/uploads/thumbs/'+files[i]).resize(W,H,{fit:'cover'}).toBuffer();const x=(i%cols)*W,y=Math.floor(i/cols)*H;
comp.push({input:b,left:x,top:y});comp.push({input:Buffer.from(`<svg width="46" height="30"><rect width="46" height="30" fill="#000" opacity=".75"/><text x="23" y="22" font-size="20" fill="#ffd21a" text-anchor="middle" font-family="Arial" font-weight="bold">${i}</text></svg>`),left:x,top:y});}
await s({create:{width:W*cols,height:H*rows,channels:3,background:'#fff'}}).composite(comp).jpeg({quality:70}).toFile('tools/shots/c-'+cat+'.jpg');
fs.writeFileSync('tools/shots/c-'+cat+'.txt',files.map((f,i)=>i+' '+f).join('\n'));})();
