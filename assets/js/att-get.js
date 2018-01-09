function handleFileSelect(evt) {
  var files = evt.target.files; // FileList object

  // files is a FileList of File objects. List some properties.
  for (var i = 0, f; f = files[i]; i++) {
    console.log(f)
    
    
    // output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
    //   f.size, ' bytes, last modified: ',
    //   f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
    //   '</li>');
  }
}
console.log(document.getElementById('files'));
document.getElementById('filesATT').addEventListener('change', handleFileSelect, false);



// $.ajax({
//   url: "test.php",
//   type: "POST", //send it through get method
//   data: {  
//     F27: UserID, 
//     F28: EmailAddress,
//     F29: xxx
//   },
//   success: function(response) {
//     //Do Something
//   },
//   error: function(xhr) {
//     //Do Something to handle error
//   }
// });

// (function () {
//   var desired_value

//   elekReq.open('GET', `http://127.0.0.1/examples/cvs/elek_SK-1_30%2C00.xlsx`)
//   elekReq.responseType = 'arraybuffer'
//   elekReq.onload = () => {
//     var data = new Uint8Array(elekReq.response);
//     var workbook = XLSX.read(data, { type: "array" });

//     renderData(workbook)
//   }
//   elekReq.send()

//   // for att
//   attReq.open('GET', `http://127.0.0.1/examples/cvs/ATT_SK-1_30%2C00.xls`)
//   attReq.responseType = 'arraybuffer'
//   attReq.onload = () => {
//     var dataATT = new Uint8Array(attReq.response);
//     var workbookATT = XLSX.read(dataATT, { type: "array" });
//     renderATT(workbookATT)
//   }
//   attReq.send()


//   // Prepare desired value for entering ATT
//   function renderData(workbook) {
//     var first_sheet_name = workbook.SheetNames[0];
//     var address_of_cell = 'G42';

//     /* Get worksheet */
//     var worksheet = workbook.Sheets[first_sheet_name];

//     /* Find desired cell */
//     var desired_cell = worksheet[address_of_cell];
//     console.log('cell style', desired_cell.s)
//     /* Get the value */
//     desired_value = (desired_cell ? desired_cell.w : undefined);
//     console.log(workbook)
//   }

//   function renderATT(workbookATT) {
//     console.log('desired value: ', desired_value)

//     var first_sheet_name_ATT = workbookATT.SheetNames[0]
//     var address_of_cell_ATT = 'F27'

//     /* Get worksheet */
//     var worksheet_ATT = workbookATT.Sheets[first_sheet_name_ATT]

//     /* Find desired cell */
//     var desired_cell_ATT = worksheet_ATT[address_of_cell_ATT]

//     /* Get the value */
//     console.log('ATT-30', workbookATT)
//     desired_cell_ATT.f = null
//     desired_cell_ATT.w = desired_value
//     desired_cell_ATT.v = desired_value

//     saveFile(workbookATT)

//   }

//   // function saveFile(workbook) {
//   //   /* bookType can be any supported output type */
//   //   var wopts = { bookType: 'xls', bookSST: true, type: 'array' };

//   //   var wbout = XLSX.write(workbook, wopts);

//   //   /* the saveAs call downloads a file on the local machine */
//   //   saveAs(new Blob([wbout], { type: "application/octet-stream" }), "test.xls");
//   // }

// })()