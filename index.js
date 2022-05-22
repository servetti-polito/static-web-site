const fs = require('fs');
const folder = './_canti/testi/';

fs.readdirSync(folder).forEach(file => {
  console.log(file);
});