    <nav class='navbar navbar-expand-md bg-body-tertiary'>
        <div class='container'>
            <a class='navbar-brand' href='<?= route('') ?>'>
                <img src='<?= asset('images/logo.png') ?>' alt='logo' class='img-fluid'>
            </a>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                <ul class='navbar-nav ms-auto mb-2 mb-md-0'>
                    <li class='nav-item'>
                        <a class='nav-link active' aria-current='page' href='<?= route('/') ?>'>Home</a>
                    </li>


                    <?php
                    $homeLink = route('');
                    $profileLink = route('/profile');
                    $registerLink = route('auth/register');

                    if (isAuth("admin")) {
                        $registerLink = route('auth/createNewUser');
                    }

                    $loginLink = route('auth/login');
                    $logoutLink = route('auth/logout');

                    if (isAuth('admin')) {

                        $name = auth('name');
                        echo "                    
                        <li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' href='' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                {$name}
                            </a>
                            <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='{$profileLink}'>Profile</a></li>
                                <li><a class='dropdown-item' href='{$registerLink}'>Create New Admin</a></li>
                                <li><a class='dropdown-item' href='{$logoutLink}'>Logout</a></li>
                            </ul>
                        </li>";
                    } else if (isAuth("customer")) {
                        $name = auth('name');
                        echo "                    
                        <li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' href='' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                {$name}
                            </a>
                            <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='{$profileLink}'>Profile</a></li>
                                <li><a class='dropdown-item' href='{$logoutLink}'>Logout</a></li>
                            </ul>
                        </li>";
                    } else {
                        echo "                    
                        <li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' href='' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                Must be Login
                            </a>
                            <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='{$loginLink}'>Login</a></li>
                                <li><a class='dropdown-item' href='{$registerLink}'>Register</a></li>
                            </ul>
                        </li>";
                    }


                    ?>









                </ul>
            </div>
        </div>
    </nav>