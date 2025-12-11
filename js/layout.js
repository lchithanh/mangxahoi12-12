document.addEventListener('DOMContentLoaded', () => {
    const btnChude = document.getElementById('btnChude');
    const dropdownChude = document.getElementById('dropdownChude');

    if(btnChude && dropdownChude){
        btnChude.addEventListener('click', () => {
            if(dropdownChude.classList.contains('show')){
                dropdownChude.style.maxHeight = dropdownChude.scrollHeight + "px";
                requestAnimationFrame(() => {
                    dropdownChude.style.maxHeight = "0";
                    dropdownChude.style.opacity = "0";
                });
                setTimeout(() => dropdownChude.classList.remove('show'), 400);
            } else {
                dropdownChude.classList.add('show');
                const scrollHeight = dropdownChude.scrollHeight;
                dropdownChude.style.maxHeight = "0";
                dropdownChude.style.opacity = "0";
                requestAnimationFrame(() => {
                    dropdownChude.style.maxHeight = scrollHeight + "px";
                    dropdownChude.style.opacity = "1";
                });
            }
        });
    }
});
