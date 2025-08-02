const menuBotao = document.querySelector("[data-menu-botao]");
const menuLista = document.querySelector("[data-menu-lista]");
function clickMenu() {
  menuLista.classList.toggle("ativo");
}
if (menuBotao) {
  menuBotao.addEventListener("click", clickMenu);
}

const formLogin = document.querySelector("[data-form-login]");

function logando(event) {
  event.preventDefault();
  window.location = "perfil.html";
}
if (formLogin) {
  formLogin.addEventListener("submit", logando);
}
