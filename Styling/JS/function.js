        // Function to update total price
        function calculateTotal() { 
            // Select all checked checkboxes
            let checkboxes = document.querySelectorAll('input[name="selected_products[]"]:checked');
            let total = 0;
    
            checkboxes.forEach(checkbox => {
                // Find the closest cart-item element and get its final-price element
                let cartItem = checkbox.closest('.cart-item');
                let finalPriceElement = cartItem.querySelector('.final-price');
                if (finalPriceElement) {
                    // Extract and parse the price value from the final-price element
                    let finalPrice = parseFloat(finalPriceElement.textContent.replace(/IDR\s|,/g, ''));
                    total += finalPrice;
                }
            });
    
            document.getElementById('total-price').textContent = `IDR ${total.toLocaleString()}`;
            }

document.addEventListener("DOMContentLoaded", function () {
    let profileDropdownList = document.querySelector(".profile-dropdown-list");
    let btn = document.querySelector(".profile-dropdown-btn");

    const toggle = () => {
        if (profileDropdownList) {
            profileDropdownList.classList.toggle("active");
        }
    };

    if (btn) {
        btn.addEventListener("click", toggle);
    }

    window.addEventListener("click", function (e) {
        if (btn && !btn.contains(e.target)) {
            profileDropdownList?.classList.remove("active");
        }
    });
});

let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}