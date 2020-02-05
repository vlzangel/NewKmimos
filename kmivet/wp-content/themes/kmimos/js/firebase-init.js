var firebaseConfig = {
    apiKey: "AIzaSyCJ3U368AbKjfn7qRZxBt5b9tWO_0yL-_M",
    authDomain: "kmivet.firebaseapp.com",
    databaseURL: "https://kmivet.firebaseio.com",
    projectId: "kmivet",
    storageBucket: "kmivet.appspot.com",
    messagingSenderId: "816587933523",
    appId: "1:816587933523:web:b6b5ef64997209f2f443a6",
    measurementId: "G-WGCS06RXVE"
};
firebase.initializeApp(firebaseConfig);

function generar_token(email){
    firebase.auth().createUserWithEmailAndPassword(email, "123456").then(function(user){
        console.log(user.user);
        console.log(user.user.uid);
        console.log(user.user.email);

        send_uid(user.user.email, user.user.uid);

    }).catch(function(error) {
        var errorCode = error.code;
        var errorMessage = error.message;
        console.log(error);
    });
}

function send_uid(email, uid){
    jQuery.post(
        AJAX+"?action=kv&m=auth&a=send", {
            email: email,
            uid: uid
        },
        function(r){
            console.log(r);
        },
        'json'
    );
}

if( Cookies.get('userEmail') != undefined ){
    messaging = firebase.messaging();
    messaging.requestPermission().then(function () {
        console.log('grant');
        messaging.getToken().then(function (currentToken) {
            console.log('current token', currentToken);
            send_uid( Cookies.get('userEmail'), currentToken);
        });
    }).catch(function(error) {
        console.log('Push Message is disallowed');
    });
}


/*
firebase.auth().signInWithEmailAndPassword("kmivettres@mail.com", '123456').then(function(user){
    messaging.onMessage(function(payload) {
        console.log("Notificación recibida ", payload);
    });

    firebase.messaging().requestPermission()
    .then(function(token) {
        console.log('Recibido permiso.');
        console.log(token);

        messaging.getToken().then(function (currentToken) {
             console.log('current token', currentToken);
        });
        
    })
    .catch(function(err) {
        console.log('No se ha obtenido permiso', err);
    });
}).catch(function(error) {
    var errorCode = error.code;
    var errorMessage = error.message;
    console.log(error);
});
*/



    