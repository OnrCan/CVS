var elekDropzone = document.getElementById('elek-dropzone');
var attDropzone = document.getElementById('att-dropzone');
var submitButton = document.getElementById('elekSubmit');
var processStatusText = document.getElementById('processStatus');
// var resetElekButton = document.getElementById('resetElekFiles');
// var resetAttButton = document.getElementById('resetAttFiles');

window.Dropzone.options.elekDropzone = {
  paramName: 'elekFile',
  // uploadMultiple: true,
  dictDefaultMessage: "ELEK FILES HERE",
  autoProcessQueue: false,
  addRemoveLinks: true,
  parallelUploads: 5000,
  init: function () {
    var elekzone = this;
    submitButton.onclick = function (e) {
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      elekzone.processQueue();
    };

    this.on('queuecomplete', function() {
      // sendDestroy();
      let sub = document.getElementById('hiddenSubmit');
      sub.click();
      elekzone.removeAllFiles();
      processStatusText.innerHTML = `
        <strike>ATT Files are uploading...</strike><br>
        <strike>ELEK Files are uploading...</strike><br>
        File uploading has been completed. Please wait for the server response!
        `;
      // resetElekButton.style.opacity = 1;
      // resetElekButton.onclick = this.removeAllFiles;
    });
  }
}

window.Dropzone.options.attDropzone = {
  paramName: 'attFile',
  // uploadMultiple: true,
  dictDefaultMessage: "ATT FILES HERE",
  autoProcessQueue: false,
  addRemoveLinks: true,
  parallelUploads: 5000,
  init: function () {
    var attzone = this;
    this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
      $('#modal1').modal('open');
      processStatusText.innerHTML = `
        ATT Files are uploading...
      `;
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      sendHash();
      setTimeout(function() {
        attzone.processQueue();
      }, 1000)
    });

    this.on('queuecomplete', function() {
      submitButton.click();
      processStatusText.innerHTML = `
        <strike>ATT Files are uploading...</strike><br>
        ELEK Files are uploading...
        `;
      attzone.removeAllFiles();
      // resetAttButton.style.opacity = 1;
      // resetAttButton.onclick = this.removeAllFiles;
    });
  }
}

function sendHash() {
  const hash = md5('' + Math.floor((Math.random() * 10000) + 0.1) + Math.floor((Math.random() * 10000) + 0.1));
  var uri = "./test.php";
  var xhr = new XMLHttpRequest();
  var fd = new FormData();

  xhr.open("POST", uri, true);
  fd.append('hash', hash);
  // Initiate a multipart/form-data upload
  xhr.send(fd);
}

// function sendDestroy() {
//   var uri = "./test.php";
//   var xhrDestroy = new XMLHttpRequest();
//   var fd = new FormData();
  
//   xhrDestroy.open("POST", uri, true);
//   // xhrDestroy.setRequestHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8')
//   fd.append('destroy', 'files are done');
//   // Initiate a multipart/form-data upload
//   xhrDestroy.send(fd);
// }
// function uploadFiles() {
//   const hash = md5("ipek60" + Math.floor((Math.random() * 10000) + 0.1) + Math.floor((Math.random() * 10000) + 0.1));
//   // console.log(attFileList);
//   // for (const file of attFileList.files) {
//   //   console.log(file);
//   //   // templateATTInput.files.FileList.push(file);    
//   // }
//   // for (const file of elekFileList.files.FileList) {
//   //   templateELEKInput.files.push(file);    
//   // }
// }