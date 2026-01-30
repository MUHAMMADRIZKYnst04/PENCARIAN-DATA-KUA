document.addEventListener('DOMContentLoaded', function() {
    var dz = document.querySelector('.import-dropzone');
    if (!dz) return;
    var input = document.querySelector('#importFile');
    dz.addEventListener('click', function(){ input.click(); });
    ['dragover','dragleave','drop'].forEach(function(evt){
        dz.addEventListener(evt, function(e){ e.preventDefault(); });
    });
    dz.addEventListener('dragover', function(){ dz.classList.add('dragover'); });
    dz.addEventListener('dragleave', function(){ dz.classList.remove('dragover'); });
    dz.addEventListener('drop', function(e){
        dz.classList.remove('dragover');
        if(e.dataTransfer.files.length){ input.files = e.dataTransfer.files; }
    });
});

// Dark/Light mode toggle
const btn=document.getElementById('themeToggle');
if(btn){
  btn.addEventListener('click',()=>{
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme',document.body.classList.contains('dark-mode')?'dark':'light');
  });
  if(localStorage.getItem('theme')==='dark'){document.body.classList.add('dark-mode');}
}
