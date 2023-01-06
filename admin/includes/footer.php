        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <?php
        if(isset($page_scripts)) {
            foreach($page_scripts as $script)
                echo '<script src="'.$script.'"></script>';
        }
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>