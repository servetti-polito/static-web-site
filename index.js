const fs = require('fs');
const folder = './_canti/testi/';
const dir = './dist';

if (!fs.existsSync(dir)){
  fs.mkdirSync(dir);
}


const filelist = fs.readdirSync(folder).join('\n');
console.log(filelist);

fs.writeFileSync([dir, 'filelist.txt'].join('/'), filelist);