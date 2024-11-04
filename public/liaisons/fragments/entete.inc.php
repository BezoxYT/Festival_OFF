<header role="header">
    <input type="checkbox" id="menu-toggle">
    <label id="burger" for="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
    </label>
    
    <nav id="menu" aria-label="Principale">
        <div class="logo">
            <a href="index.php">
            <img src="liaisons/images/png/logoOff_blanc.png" alt="Logo Festival OFF de Québec">
        </a>
        </div>
        <ul class="nav-links">
        <li class="dropdown">
                <a href="index.php">LE OFF <span class="arrow">▼</span></a>
                <ul class="dropdown-content">
                    <li><a href="#">Lieux</a></li>
                    <li><a href="#">Tarifs</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="<?php echo $niveau;?>public/programmation/index.php">PROGRAMMATION <span class="arrow">▼</span></a>
                <ul class="dropdown-content">
                    <li><a href="#">Par lieu</a></li>
                    <li><a href="#">Par Dates</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">PARTENAIRES <span class="arrow">▼</span></a>
                <ul class="dropdown-content">
                    <li><a href="#">Partenaires actuels</a></li>
                    <li><a href="#">Devenir partenaire</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="<?php echo $niveau;?>artistes/index.php">ARTISTES <span class="arrow">▼</span></a>
                <ul class="dropdown-content">
                    <li><a href="#">Artistes A-Z</a></li>
                    <li><a href="#">Par style musical</a></li>
                </ul>
            </li>
            <li><a href="#" class="btn_Acheter_Passeport">Acheter mon passeport</a></li>
        </ul>
    </nav>
    
    <div class="bannerr">
        <h1>FESTIVAL OFF DE QUÉBEC</h1>
        <p>DU 11 AU 14 JUILLET</p>
    </div>
    
    <svg class="wave1" width="1440" height="300" viewBox="0 0 1440 300" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0H1440V202C1440 202 1348.58 218.454 1289.5 217.5C1215.07 216.298 1175.94 186.083 1101.5 186C1010.01 185.898 963.244 226.356 872 233C750.655 241.835 683.459 194.896 562 202C438.377 209.23 376.195 276.868 252.5 271C150.392 266.156 0 202 0 202V0Z" fill="#7720D4"/>
    </svg>
    
    <svg class="wave2" fill="none" height="300" viewBox="0 23 1357 300" width="1407" xmlns="http://www.w3.org/2000/svg">
        <path d="m0 0h1440v230s-91.42 16.454-150.5 15.5c-74.43-1.202-113.56-31.417-188-31.5-91.49-.102-138.256 40.356-229.5 47-121.345 8.835-188.541-38.104-310-31-123.623 7.23-185.805 74.868-309.5 69-102.108-4.844-252.5-69-252.5-69z" fill="#ff4fbc"/>
    </svg>
</header>
