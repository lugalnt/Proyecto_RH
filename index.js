 const SideMenu = document.querySelector("aside");
const menuBtn = document.querySelector("#menu-btn");
const closeBtn = document.querySelector("#close-btn");
const themeToggler = document.querySelector(".theme-toggler"); 

//Mostrar la barra lateral.
menuBtn.addEventListener('click', () => {
    SideMenu.style.display = 'block';
})

//Cerrar la barra lateral. 
closeBtn.addEventListener('click', () => {
    SideMenu.style.display = 'none';
})

//Cambiar de modo (claro/oscuro).
themeToggler.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme-variables');

    themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
})  


//Llenar las prestaciones en la tabla.
Orders.forEach(order => {
    const tr = document.createElement('tr');
    const trContent = `
                    <td>${order.prestacionNombre}</td>
                    <td>${order.empleadoNombre}</td>
                    <td>${order.prestacionNumero}</td>
                    <td class="${order.estado === 
                    'Rechazado' ? 'danger' : order.estado
                    === 'Pendiente' ? 'warning' : order.estado
                    === 'Aprovado' ? 'success' : 'primary'
                    }">${order.estado}</td>
                    <td class="primary"> </td>
                    `;

    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
})