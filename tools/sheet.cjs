// node sheet.cjs <out> <dir...>  -> kontaktný list náhľadov s číslami
const s=require('./node_modules/sharp'),fs=require('fs'),path=require('path');
const [out,...dirs]=process.argv.slice(2);
(async()=>{
  let files=[];for(const d of dirs){for(const f of fs.readdirSync('assets/uploads/'+d).sort())if(/\.(jpe?g)$/i.test(f))files.push(d+'/'+f)}
  files=files.slice(0,30);
  const W=300,H=225,cols=6,rows=Math.ceil(files.length/cols);
  const comp=[];
  for(let i=0;i<files.length;i++){
    const b=await s('assets/uploads/thumbs/'+files[i]).resize(W,H,{fit:'cover'}).toBuffer();
    const x=(i%cols)*W,y=Math.floor(i/cols)*H;
    comp.push({input:b,left:x,top:y});
    comp.push({input:Buffer.from(`<svg width="46" height="30"><rect width="46" height="30" fill="#000" opacity=".75"/><text x="23" y="22" font-size="20" fill="#ffd21a" text-anchor="middle" font-family="Arial" font-weight="bold">${i}</text></svg>`),left:x,top:y});
  }
  await s({create:{width:W*cols,height:H*rows,channels:3,background:'#fff'}}).composite(comp).jpeg({quality:70}).toFile('tools/shots/'+out+'.jpg');
  fs.writeFileSync('tools/shots/'+out+'.txt',files.map((f,i)=>i+' '+f).join('\n'));
})();
