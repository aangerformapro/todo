const e=new Set;function t(t){e.has(t)||(e.add(t),t.classList.add("dismiss"),setTimeout((()=>{t.remove()}),800))}document.querySelectorAll(".notifications .alert-dismissible").forEach((e=>{e.addEventListener("click",(({target:s})=>{s.closest(".btn-close")&&t(e)})),setTimeout((()=>{e.parentElement&&t(e)}),2e3)}));
