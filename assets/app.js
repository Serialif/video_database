/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
// import './bootstrap';

import './scripts/alerts'

const $menuItems = document.querySelectorAll(".menu > li")

for (const $menuItem of $menuItems) {
    const width = Math.ceil(100 / $menuItems.length)
    $menuItem.style.width = width + '%'
    $menuItem.style.mawwidth = width + '%'
    console.log($menuItem.style.width)
}

// width: 34%;
// max-width: 34%;