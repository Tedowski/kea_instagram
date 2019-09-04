<header>
    <nav class="wrapper header__wrapper grid grid__2_8_2">
        <a class="link_block pointer img" href="home.php">
            <img src="./media/ig_logo.png" alt="Instagram">
        </a>
        <div class="dropdown flex__center">
            <form class="header__form mg_x__16" id="searchBar">
                <input type="text" name="txtName" id="txtName" placeholder="Search..">
                <i class="fas fa-search"></i>
            </form>
            <div class="dropdown-content box" id="showBar"></div>
            <template id="itemSearchTemplate">
                <a class="grid grid__3_9 pd_y__8 a-search">
                    <div class="img-round wh-34 mg_x__8">
                        <img class="search_img" src="" alt="Profile">
                    </div>
                    <h5 class="username"></h5>
                </a>
            </template>
        </div>
        <div class="g_item__end">
            <a class="link_block nav__button pointer" href="upload.php"><i class="fas fa-plus"></i></a>
            <a class="link_block mg_l__24 nav__button pointer" href="profile.php"><i class="far fa-user"></i></a>
        </div>
    </nav>
</header>