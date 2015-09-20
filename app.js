
// define Firebase ref
var firebaseBaseUrl = "https://gdgevents.firebaseIO.com";
var ref = new Firebase(firebaseBaseUrl);

//Login Info
var authData = ref.getAuth();

if (authData) {
  console.log("User " + authData.uid + " is logged in with " + authData.provider);
  showLogin(false);
} else {
  console.log("User is logged out");
  showLogin(true);
}

function showLogin(tf){
    document.getElementById('loginbutton').style.display = tf == true ? 'block' : 'none';
    document.getElementById('logoutbutton').style.display = tf == true ? 'none' : 'block';
//    document.getElementById('shwrapper').style.display = tf == true ? 'none' : 'block';
}



//  better to have user action call a popup for browser security
function login(){
    ref.authWithOAuthPopup("google", authHandler, {
      remember: "default"
      ,scope: "email"
    });
}
function logout(){
    ref.unauth();
    showLogin(true);
}


// Create a callback to handle the result of the authentication
function authHandler(error, authData) {
  if (error) {
    console.log("Login Failed!", error);
    showLogin(true);
  } else {
    console.log("Authenticated successfully with payload:", authData);

    //  save the info about the user who just logged in
    ref.child("users").child(authData.uid).update({
      provider: authData.provider,    // only using google for this but whatevs
      name: authData.google.displayName,
      googleObject: authData.google,  // tuck the whole google obj away for later
      lastLoggedIn: Firebase.ServerValue.TIMESTAMP
    });
    authData = ref.getAuth();
    showLogin(false);
  }
}

function addNewGDG(newGDG){

  // Check Chapter
  $.get( firebaseBaseUrl+"/chapters/"+newGDG+".json" )
    .done(function( data ) {
      if(data == null){  // chapter is not already loaded, make a new request
        $.get("lib/getNewGroup.php",{meetup: newGDG})
        .done(function(data){
          if (data == "success"){
            //
          } else{
            alert(data);
          }

        });

      }
    });

  //ref.child("chapters/"+newGDG).set({ first: 'Fred', last: 'Flintstone' });
}
