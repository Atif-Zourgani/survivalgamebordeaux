const quiteBtn = document.getElementById('quiteBtn');
const quiteContainer = document.getElementById('quiteContainer');
const closeBtns = document.querySelectorAll('.closeWindow');

console.log(closeBtns)

//ferme la modal si click exterieur a la modal
document.addEventListener('click', function(event) {
    const isClickInsideModal = document.getElementById('quiteContainer').contains(event.target);
    const isClickInsideBtn = quiteBtn.contains(event.target);
    //Si click hors du btn d'ouverture de modal 
    if (!isClickInsideBtn) {
        //si click hors de la modal et si modal est en displayblock
        if (quiteContainer.classList.contains("quiteContainer") && !isClickInsideModal){ 
            //function de fermeture de modal  
            closeWindows();
        }
    }
});

//function qui ferme la fenetre
function closeWindows(){
    quiteContainer.classList.remove('quiteContainer');
    quiteContainer.classList.add('displayNone');
}

quiteBtn.addEventListener('click', ()=>{
    quiteContainer.classList.remove('displayNone');
    quiteContainer.classList.add('quiteContainer');
});

closeBtns.forEach(btn => {
    btn.addEventListener('click', closeWindows);
});
