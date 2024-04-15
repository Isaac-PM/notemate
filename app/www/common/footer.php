<footer id="footer">
    <div class="caveatFont">
        <i class="bi bi-github"></i> <a class="aOverDark" href="https://github.com/Isaac-PM">Isaac-PM</a> CC BY-SA 4.0
    </div>
</footer>

<script>
    function resizeMainContent() {
        let headerHeight = document.getElementById("header").offsetHeight;
        let footerHeight = document.getElementById("footer").offsetHeight;
        let mainContent = document.getElementById("mainContent");
        let viewportHeight = window.innerHeight;
        mainContent.style.height = `${viewportHeight - headerHeight - footerHeight}px`;
        mainContent.style.marginTop = `${headerHeight}px`;
        mainContent.style.marginBottom = `${footerHeight}px`;
    }

    window.addEventListener("resize", resizeMainContent);
    window.addEventListener("load", resizeMainContent);
    window.route = resizeMainContent;
</script>