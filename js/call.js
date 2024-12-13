document.getElementById("open-modal-btn").addEventListener("click",function(){
    document.getElementById("call-modal").classList.add("open")
})

document.getElementById("close-call-modal-btn").addEventListener("click",function(){
    document.getElementById("call-modal").classList.remove("open")
})

window.addEventListener('keydown', (e) =>{
    if (e.key === "Escape") {
        document.getElementById("call-modal").classList.remove("open");
    }
});