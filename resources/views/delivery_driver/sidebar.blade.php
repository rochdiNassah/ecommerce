<script>
    var openSidebarButton = document.getElementById('open-dashboard-sidebar')
    var closeSidebarButton = document.getElementById('close-dashboard-sidebar')
    var sidebar = document.getElementById('dashboard-sidebar')

    openSidebarButton.addEventListener('click', function () {
        openSidebar()
    })
    closeSidebarButton.addEventListener('click', function () {
        closeSidebar()
    })

    function openSidebar()
    {
        removeClass(sidebar, 'hidden')
        addClass(openSidebarButton, 'hidden')

        closeSidebarButton.style.left = '10px'
        sidebar.style.left = '0'
    }
    function closeSidebar()
    {
        closeSidebarButton.style.left = '-800px'
        sidebar.style.left = '-800px'
        
        addClass(sidebar, 'closed')
        removeClass(openSidebarButton, 'hidden')
    }
</script>