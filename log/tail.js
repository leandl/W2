const fs = require('fs');

const controller = {
	files: {
		log: {
			path: `${__dirname}/Log.log`,
			textInit: '======>> Log.log <<======',
			content: ''
		},
		phpLog: {
			path: `${__dirname}/PHPLog.log`,
			textInit: '======>> PHPLog.log <<======',
			content: ''
		}
	},
	lastFileModification: ''
}


function clearFiles(){
	for (let index in controller.files) {
		const file = controller.files[index];
		fs.writeFileSync(file.path, '', (err) => {if(err){throw err;}});
	}
	console.log('======>> Iniciou <<======');
} 


function writeFiles(){
	for (let index in controller.files) {
		const file = controller.files[index];
		const contentFile = fs.readFileSync(file.path,  "utf-8");

		if(contentFile !== file.content){
			if(controller.lastFileModification !== index){
				console.log(`\n${file.textInit}`);
				controller.lastFileModification = index;
			}
			console.log(contentFile.replace(file.content, '').replace('/^\n/', ''));
			file.content = contentFile;
		}
	}
}


clearFiles();
setInterval(writeFiles, 500);