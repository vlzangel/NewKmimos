<!DOCTYPE html>
<html>
    <head>
        <title></title>

        <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

        <script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-auth.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-database.js"></script>
        <script>
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
            function registrar(email){
                firebase.auth().createUserWithEmailAndPassword(email, "123456").then(function(user){
                    console.log(user.user);
                    console.log(user.user.uid);
                    console.log(user.user.email);
                }).catch(function(error) {
                    var errorCode = error.code;
                    var errorMessage = error.message;
                });
            }
            registrar("jose3@mail.com");
        </script> -->

        <script type="text/javascript">
            function show_notification(msg) {
                if (!("Notification" in window)) {
                    alert("Este navegador no soporta las notificaciones del sistema");
                } else if (Notification.permission === "granted") {
                    var notification = new Notification( msg );
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission(function (permission) {
                        if (permission === "granted") {
                            var notification = new Notification( msg );
                        }
                    });
                }
            }
        </script>

    </head>
    <body>

    </body>
</html>

        