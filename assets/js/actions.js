let domTabLogin = document.getElementById("tabsignup");
if (domTabLogin !== null) {
    document.getElementById("tabsignup").addEventListener('click', (e) => {
        document.getElementById('signupFrame').classList.remove('hidden');
        document.getElementById('signupFrame').classList.add("show");
        document.getElementById('signinFrame').classList.remove('show');
        document.getElementById('signinFrame').classList.add("hidden");
    });
}
let domTabSignup = document.getElementById("tabsignin");
if (domTabSignup !== null) {
    document.getElementById("tabsignin").addEventListener('click', (e) => {
        document.getElementById('signupFrame').classList.remove('show');
        document.getElementById('signupFrame').classList.add("hidden");
        document.getElementById('signinFrame').classList.remove('hidden');
        document.getElementById('signinFrame').classList.add("show");
    });
}

let logoutBut = document.getElementById('logout');
if (logoutBut !== null) {
    logoutBut.addEventListener('click', () => {
        let logOut = confirm("Do you really want logout ?");
        if (logOut) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.response);
                    alert(this.response + " are logout !");
                    document.location.href = "index.php";
                }
            }
            xmlhttp.open("GET", `index.php?action=logout`);
            xmlhttp.send();
        }
    });
}