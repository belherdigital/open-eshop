
if($('#offer_valid').length != 0){
        $('#offer_valid').datepicker();}
if($('#featured').length != 0){
        $('#featured').datepicker();}

$('.fileinput').fileinput();


function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // files is a FileList of File objects. List some properties.
    var output = [];
    for (var i = 0, f; f = files[i]; i++) {
    	var fileSize = Math.round(parseInt(f.size)/1024);

    	if(fileSize/1024 < 1)
		  unitSize = 'KB';
		else{
			unitSize = 'MB';
		  	fileSize = Math.round(fileSize/1024);	
		}

      	output.push('<strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                  fileSize+unitSize, ' , last modified: ',
                  f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a'
                  );
    }
    document.getElementById('file-output').innerHTML = output.join('');
  }

  document.getElementById('fileupload').addEventListener('change', handleFileSelect, false);