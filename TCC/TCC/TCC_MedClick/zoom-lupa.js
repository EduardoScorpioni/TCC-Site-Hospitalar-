document.addEventListener("DOMContentLoaded", function () {
    // Criar o container da lupa
    const lupa = document.createElement("div");
    lupa.id = "lupaZoom";
    const imgZoom = document.createElement("img");
    lupa.appendChild(imgZoom);
    document.body.appendChild(lupa);

    let ativo = false;
    let screenshot = null;

    // Botão de ativar/desativar zoom (você pode estilizar depois)
    const btn = document.createElement("button");
    btn.innerText = "🔍 Ativar Zoom";
    btn.style.position = "fixed";
    btn.style.bottom = "20px";
    btn.style.right = "20px";
    btn.style.zIndex = "10000";
    btn.style.padding = "10px 15px";
    btn.style.borderRadius = "8px";
    btn.style.cursor = "pointer";
    document.body.appendChild(btn);

    // Ativar ou desativar a lupa
    btn.addEventListener("click", async function () {
        ativo = !ativo;
        if (ativo) {
            btn.innerText = "❌ Desativar Zoom";
            // Screenshot da tela (usando html2canvas)
            const canvas = await html2canvas(document.body);
            screenshot = canvas.toDataURL("image/png");
            imgZoom.src = screenshot;
            lupa.style.display = "block";
        } else {
            btn.innerText = "🔍 Ativar Zoom";
            lupa.style.display = "none";
        }
    });

    // Movimentar a lupa conforme o mouse
    document.addEventListener("mousemove", function (e) {
        if (!ativo) return;

        const size = 150;
        lupa.style.left = (e.pageX - size / 2) + "px";
        lupa.style.top = (e.pageY - size / 2) + "px";

        imgZoom.style.left = (-e.pageX * 1.5 + size / 2) + "px";
        imgZoom.style.top = (-e.pageY * 1.5 + size / 2) + "px";
    });
});
