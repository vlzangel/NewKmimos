importScripts('https://www.gstatic.com/firebasejs/7.8.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.8.0/firebase-messaging.js');
firebase.initializeApp({
	projectId: "kmivet",
	apiKey: "AIzaSyCJ3U368AbKjfn7qRZxBt5b9tWO_0yL-_M",
	appId: "1:816587933523:web:b6b5ef64997209f2f443a6",
	'messagingSenderId': '816587933523'
});
const messaging = firebase.messaging();