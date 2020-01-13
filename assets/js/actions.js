window.onload = function() {
    document.getElementById('themessages').scrollTop = document.getElementById('themessages').scrollHeight;
}
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
let btnAvatar = document.getElementById("avatar");
if (btnAvatar !== null) {
    document.getElementById("avatar").addEventListener('click', (e) => {
        document.getElementById('downloaderFile').classList.remove('hidden');
    });
}
let btnclose = document.getElementById("symbolClose");
if (btnclose !== null) {
    document.getElementById("symbolClose").addEventListener('click', (e) => {
        document.getElementById('downloaderFile').classList.add('hidden');
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



window.addEventListener('load', function() {

    this.setInterval(function() {
        // we take all members connected to compared with the database
        let allMember = document.querySelectorAll(".member-list-item");
        let connectedMember = [];
        allMember.forEach(element => {
            connectedMember.push(element.id.split('mem_')[1]);
        });
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.response !== "") {

                    let oldListeMess = document.getElementById('membersList').innerHTML;
                    document.getElementById('membersList').innerHTML = this.response;


                }
            }
        }
        xmlhttp.open("GET", `index.php?action=getMember&allconnected=${connectedMember}`);
        xmlhttp.send();
    }, 2000);

    this.setInterval(function() {
        let allMessage = document.querySelectorAll(".message-item");
        let lastMessage = allMessage.length;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.response !== "") {
                    let oldListeMess = document.getElementById('themessages').innerHTML;
                    document.getElementById('themessages').innerHTML = oldListeMess + this.response;

                }
                // rest at the bottom of the div
                //document.getElementById('themessages').scrollTop = document.getElementById('themessages').scrollHeight;

            }
        }
        xmlhttp.open("GET", `index.php?action=getMessage&lastmessage=${lastMessage}`);
        xmlhttp.send();
    }, 1000);

});