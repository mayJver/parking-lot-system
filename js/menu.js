document.addEventListener("DOMContentLoaded", function () {
    const userRole = document.documentElement.getAttribute("data-user-role") || "mecanico_b";
    
    // 1. Enlaces que ven ABSOLUTAMENTE TODOS (incluso el Mecánico B)
    let menuHTML = `
        <nav class="navbar" style="background-color: var(--color-principal, #1e293b); padding: 1rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div class="nav-logo" style="display: flex; align-items: center; gap: 10px; color: white; font-weight: bold; font-size: 1.3rem;">
                <span style="font-size: 1.6rem;">🚗</span> SOP Parking
            </div>
            <div class="nav-links" style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="parking.php" style="color: white; text-decoration: none; font-weight: 500;">📋 Parking</a>
                <a href="altas_bajas.php" style="color: white; text-decoration: none; font-weight: 500;">➕ Altas/Bajas</a>
                <a href="mantenimiento.php" style="color: white; text-decoration: none; font-weight: 500;">🔧 Mantenimiento</a>
    `;

    // 2. SOLO si el usuario NO es mecanico_b, le mostramos el botón de Reparaciones
    if (userRole !== "mecanico_b") {
        menuHTML += `
            <a href="reparacion.php" style="color: white; text-decoration: none; font-weight: 500;">⚙️ Reparaciones</a>
        `;
    }

    // 3. SOLO si el usuario es admin, le mostramos el botón de Registrar Personal
    if (userRole === "admin") {
        menuHTML += `
            <a href="registro_usuarios.php" style="background-color: var(--color-acento, #10b981); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                👤 Registrar Personal
            </a>
        `;
    }

    // 4. Botón de salida para cerrar etiquetas
    menuHTML += `
                <a href="../php/logout.php" style="color: #f87171; text-decoration: none; font-weight: bold; margin-left: 1rem;">🚪 Salir</a>
            </div>
        </nav>
    `;

    const menuContainer = document.getElementById("menu-global");
    if (menuContainer) {
        menuContainer.innerHTML = menuHTML;
    }
});
