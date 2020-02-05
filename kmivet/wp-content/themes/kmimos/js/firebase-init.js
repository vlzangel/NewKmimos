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

firebase.auth().signInWithEmailAndPassword("kmivettres@mail.com", '123456').then(function(user){

    Notification.requestPermission().then((permission) => {
  if (permission === 'granted') {
    console.log('Notification permission granted.');
    // TODO(developer): Retrieve an Instance ID token for use with FCM.
    // ...
  } else {
    console.log('Unable to get permission to notify.');
  }
});
    
    const messaging = firebase.messaging();

    messaging.usePublicVapidKey('BOKyoDDTOLAKPbgvzdg0k55N2X3866lwyRYhpPgjGmTjo561hp-fpdwp-WU1BN4FvaMDB6IsYiiJJLYRz2IQte0');
    messaging.getToken().then((currentToken) => {
        if (currentToken) {
            // sendTokenToServer(currentToken);
            // updateUIForPushEnabled(currentToken);
        } else {
            // Show permission request.
            console.log('No Instance ID token available. Request permission to generate one.');
            // Show permission UI.
            // updateUIForPushPermissionRequired();
            // setTokenSentToServer(false);
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        // showToken('Error retrieving Instance ID token. ', err);
        // setTokenSentToServer(false);
    });

    messaging.onTokenRefresh(() => {
        messaging.getToken().then((refreshedToken) => {
            console.log('Token refreshed.');
            // setTokenSentToServer(false);
            // sendTokenToServer(refreshedToken);
        }).catch((err) => {
            console.log('Unable to retrieve refreshed token ', err);
            // showToken('Unable to retrieve refreshed token ', err);
        });
    });

    messaging.onMessage((payload) => {
      console.log('Message received. ', payload);
    });

}).catch(function(error) {
    var errorCode = error.code;
    var errorMessage = error.message;
    console.log(error);
});


    