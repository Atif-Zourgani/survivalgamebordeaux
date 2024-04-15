const buttonGenerateAvatar = document.getElementById('generateAvatarModal');
const containerGenerateAvatar = document.getElementById('generateAvatarContainer');
const btnValidate = document.getElementById('btnValidate');
const btnGenerate = document.getElementById('btnGenerate');
const imgGenerateAvatar = document.getElementById('imgGenerateAvatar');
const imgAvatar = document.getElementById('imgAvatar');
const closeBtnAvatar = document.getElementById('closeBtnAvatar');
let seed = '';

//ferme la modal si click exterieur a la modal
document.addEventListener('click', function(event) {
    const isClickInsideModal = document.getElementById('generateAvatarContainer').contains(event.target);
    const isClickInsideBtn = buttonGenerateAvatar.contains(event.target);
    //Si click hors du btn d'ouverture de modal 
    if (!isClickInsideBtn) {
        //si click hors de la modal et si modal est en displayblock
        if (containerGenerateAvatar.classList.contains("displayBlock") && !isClickInsideModal){ 
            //function de fermeture de modal  
            closeModalAvatar();
        }
    }
});

//function qui ferme modal d'avatar
function closeModalAvatar(){
    containerGenerateAvatar.classList.remove('displayBlock');
    containerGenerateAvatar.classList.add('displayNone');
};

//function d'ouverture de modal
buttonGenerateAvatar.addEventListener('click', ()=>{
    containerGenerateAvatar.classList.remove('displayNone');
    containerGenerateAvatar.classList.add('displayBlock');
});

//function qui genere un avatar dice bear 
btnGenerate.addEventListener('click', ()=>{
    const caractString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const lenghtString = Math.floor(Math.random() * (8 - 3 + 1)) + 3;

    //genere une chaine de caractere
    for ( let i = 0 ; i < lenghtString ; i++ ) {
        seed += caractString.charAt(Math.floor(Math.random() * caractString.length)); 
    }

    //ajoute la chaine de caractere sur le dom pour afficher l'avatar
    imgGenerateAvatar.src = `https://api.dicebear.com/8.x/bottts/svg?seed=${seed}`;
});

closeBtnAvatar.addEventListener('click', closeModalAvatar);

//function qui valide l'avatar avec requete coté server
btnValidate.addEventListener('click', ()=>{
    if (seed == '') {
        closeModalAvatar();
    }else{
        let data = { seed: seed }; //transform seed en objet data
        fetch(generateAvatarUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken, // Envoi du token CSRF
            },
            body: JSON.stringify(data), // Converti les données en JSON
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('La requête a échoué');
            }
            return response.json();
        })
        .then(data => {
            //ajoute la chaine de caractere sur le dom pour afficher l'avatar
            imgAvatar.src = `https://api.dicebear.com/8.x/bottts/svg?seed=${seed}`;
            closeModalAvatar();
        })
        .catch((error) => {
            // ajouter une trace d'erreur sur le dom
            alert('Nous avons rencontré un problème pour modifier votre avatar.')
        });
    }
});

