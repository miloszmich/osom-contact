

function emailIsValid (email) {
  var emailString = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return emailString.test(email);
}

window.onload = () => {
  

  const form = document.getElementById('osomContactForm');
  const firstName = document.getElementById('osomContactFirstName');
  const lastName = document.getElementById('osomContactLastName');
  const login = document.getElementById('osomContactLogin');
  const email = document.getElementById('osomContactEmail');
  const city = document.getElementById('osomContactCity');
  const agreement = document.getElementById('osomContactAgree');


  email.addEventListener('keyup', () => {

    if (emailIsValid(email.value)) {
      email.classList.remove('error');
    } else {
      email.className = 'error';
    }
  });

  form.addEventListener("change", () => {
    document.getElementById('osomContactSubmitBtn').disabled = !form.checkValidity();
  });

  form.addEventListener('submit', (event) =>{
    event.preventDefault();
    const data = { 
      "first_name" : firstName.value,
      "last_name" : lastName.value,
      "login" : login.value,
      "email" : email.value,
      "city" : city.value
    };

    fetch(osomContact.root, {
      method: 'POST', 
      headers: {
        'X-WP-Nonce' : osomContact.nonce,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
      if (data.code === 200)
        alert('Wiadomość została zapisana!'),
        form.reset();
      else if (data.code === 400)
        alert('uzupełnij wszystkie pola i spróbuj ponownie!');
      else
        alert('Wystąpił błąd! Spróbuj ponownie później!');
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  });

};