</div></div>
<div class="alert-dialog-backdrop" id="app-dialog" role="dialog" aria-modal="true" aria-labelledby="app-dialog-title">
    <section class="alert-dialog">
        <div class="alert-dialog-icon"><i data-feather="help-circle"></i></div>
        <h2 id="app-dialog-title">Are you sure?</h2>
        <p id="app-dialog-message">This action cannot be undone.</p>
        <div class="alert-dialog-actions">
            <button class="alert-dialog-cancel" id="app-dialog-cancel" type="button">Cancel</button>
            <button class="alert-dialog-confirm" id="app-dialog-confirm" type="button">Continue</button>
        </div>
    </section>
</div>
<div class="alert-notification-backdrop" id="app-notification" role="alertdialog" aria-modal="true" aria-labelledby="app-notification-title">
    <section class="alert-notification <?php echo ($error ?? '') !== '' ? 'is-error' : 'is-success'; ?>">
        <div class="alert-notification-icon"><i data-feather="<?php echo ($error ?? '') !== '' ? 'alert-circle' : 'check-circle'; ?>"></i></div>
        <div><h2 id="app-notification-title"><?php echo ($error ?? '') !== '' ? 'Something needs attention' : 'All set'; ?></h2><p><?php echo e((string) (($error ?? '') !== '' ? $error : ($message ?? ''))); ?></p></div>
        <button class="menu-toggle alert-notification-close" id="close-notification" type="button" aria-label="Close notification"><i data-feather="x"></i></button>
    </section>
</div>
<script>
const appShell=document.getElementById("app-shell"),sidebar=document.getElementById("sidebar"),sidebarToggle=document.getElementById("sidebar-toggle"),topbarToggle=document.getElementById("menu-toggle");
feather.replace();
const appNotification=document.getElementById("app-notification"),closeNotification=document.getElementById("close-notification");
const hasNotification=<?php echo (($message ?? '') !== '' || ($error ?? '') !== '') ? 'true' : 'false'; ?>;
if(hasNotification){window.setTimeout(()=>{appNotification.classList.add("open");document.body.classList.add("dialog-open");},120);}
const closeNotificationDialog=()=>{appNotification.classList.remove("open");document.body.classList.remove("dialog-open");};
closeNotification.addEventListener("click",closeNotificationDialog);
appNotification.addEventListener("click",(event)=>{if(event.target===appNotification)closeNotificationDialog();});
document.addEventListener("keydown",(event)=>{if(event.key==="Escape"&&appNotification.classList.contains("open"))closeNotificationDialog();});
const toggleSidebar=()=>{
    const open=sidebar.classList.toggle("open");
    appShell.classList.toggle("sidebar-collapsed",!open);
    sidebarToggle.setAttribute("aria-expanded",String(open));
    topbarToggle.setAttribute("aria-expanded",String(open));
    sidebarToggle.setAttribute("aria-label",open ? "Close menu" : "Open menu");
    topbarToggle.setAttribute("aria-label",open ? "Close menu" : "Open menu");
};
sidebarToggle.addEventListener("click",toggleSidebar);
topbarToggle.addEventListener("click",toggleSidebar);
const appDialog=document.getElementById("app-dialog"),dialogMessage=document.getElementById("app-dialog-message"),dialogTitle=document.getElementById("app-dialog-title"),dialogCancel=document.getElementById("app-dialog-cancel"),dialogConfirm=document.getElementById("app-dialog-confirm");
let pendingForm=null;
const closeAppDialog=()=>{appDialog.classList.remove("open");document.body.classList.remove("dialog-open");pendingForm=null;};
document.querySelectorAll("form[data-confirm]").forEach((form)=>{
    form.addEventListener("submit",(event)=>{
        if(form.dataset.confirmed==="true"){form.dataset.confirmed="";return;}
        event.preventDefault();
        pendingForm=form;
        dialogTitle.textContent=form.dataset.confirmTitle||"Are you sure?";
        dialogMessage.textContent=form.dataset.confirm;
        dialogConfirm.textContent=form.dataset.confirmAction||"Continue";
        appDialog.classList.add("open");document.body.classList.add("dialog-open");
    });
});
dialogCancel.addEventListener("click",closeAppDialog);
dialogConfirm.addEventListener("click",()=>{if(!pendingForm)return;pendingForm.dataset.confirmed="true";closeAppDialog();pendingForm.submit();});
appDialog.addEventListener("click",(event)=>{if(event.target===appDialog)closeAppDialog();});
document.addEventListener("keydown",(event)=>{if(event.key==="Escape"&&appDialog.classList.contains("open"))closeAppDialog();});
</script></body></html>
