        </div>
    </main>
</div>
<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('portalSidebar').classList.toggle('open');
});
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('portalSidebar');
    if (window.innerWidth <= 760 && sidebar.classList.contains('open') && !sidebar.contains(event.target) && !event.target.closest('#sidebarToggle')) {
        sidebar.classList.remove('open');
    }
});
</script>
</body>
</html>

