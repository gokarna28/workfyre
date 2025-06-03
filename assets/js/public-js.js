$(document).ready(function () {
    // Handle register form submission
    $('#userRegisterForm').submit(function (event) {
        event.preventDefault();


        var firstName = $(this).find('input[name="firstname"]').val();
        var lastName = $(this).find('input[name="lastname"]').val();
        var email = $(this).find('input[name="email"]').val();
        var password = $(this).find('input[name="password"]').val();
        var confirmPassword = $(this).find('input[name="confirm_password"]').val();

        if (firstName === '' || lastName === '' || email === '' || password === '' || confirmPassword === '') {
            $('#successMessage').html(`
           <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">All Fields Required</div>
            `)
            return;
        }
        // Validate first and last name: must contain only letters
        var namePattern = /^[A-Za-z]+$/;
        if (!namePattern.test(firstName)) {
            $('#firstnameMessage').html(`First Name can only contain letters.`)
            return;
        }

        if (!namePattern.test(lastName)) {
            $('#lastnameMessage').html(`First Name can only contain letters.`)
            return;
        }

        if (password !== confirmPassword) {
            $('#confirmPasswordMessage').html(`Password do not Matched.`)
            return;
        }

        // Validate email format
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            $('#emailMessage').html(`Please enter a valid email address.`)
            return;
        }
        var formdata = {
            firstname: firstName,
            lastname: lastName,
            email: email,
            password: password,
            confirmPassword: confirmPassword,
            action: 'user_register',
        }
        // console.log(formdata)
        ajaxRegisterLogin(formdata);
    });


    // Handle register form submission
    $('#userLoginForm').submit(function (event) {
        event.preventDefault();

        // Serialize form data
        var email = $(this).find('input[name="email"]').val();
        var password = $(this).find('input[name="password"]').val();

        // Basic validation
        if (email === '' || password === '') {
            $('#successMessage').html(`
           <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">All Fields Required</div>
            `)
            return;
        }

        // Validate email format
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            $('#emailMessage').html(`Please enter a valid email address.`)
            return;
        }

        var formdata = {
            email: email,
            password: password,
            action: 'user_login',
        }
        ajaxRegisterLogin(formdata);
    });

    function ajaxRegisterLogin(data) {
        $.ajax({
            type: 'POST',
            url: 'http://workfyre.local/main/dashboard/ajax-register-login.php',
            data: data,
            success: function (response) {
                console.log(response);
                if (response.status == 'success') {
                    $('#successMessage').html(`
                    <div class="bg-[#d3f5cb] text-[#5F9747] border border-[#5F9747] rounded-lg py-3 px-4 text-xl">${response.message}</div>
                     `)
                    setTimeout(() => {
                        window.location.href = '/main/dashboard/home.php';
                    }, 2000);
                } else {
                    $('#successMessage').html(`
                    <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                     `)
                }
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    }



    /**update project meta start */
    $('#acceptTeamToProject').on('submit', function (e) {
        e.preventDefault();
        var invite_id = $(this).find('input[name="invite_id"]').val();
        var inviteData = {
            invite_id: invite_id,
            invite_statu: 'enrolled',
            action: 'accept_invite'
        }

        $.ajax({
            type: 'POST',
            url: 'http://workfyre.local/main/dashboard/ajax-register-login.php',
            data: inviteData,
            success: function (response) {
                console.log(response);
                if (response.status == 'success') {
                    $('#successMessage').html(`
                <div class="bg-green-100 text-green-300 border border-green-300 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                 `)
                    setTimeout(() => {
                        window.location.href = '/main/login.php';
                    }, 2000);
                } else {
                    $('#successMessage').html(`
                <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                 `)
                }
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });

    })
    /**update project meta end */

    /**slider section */
    let currentIndex = 1;
    const slideWidth = 336;
    const $slider = $('#slider');
    let isTransitioning = false;
    
    // Clone first and last
    const $slides = $slider.children();
    const $firstClone = $slides.first().clone();
    const $lastClone = $slides.last().clone();
    
    $slider.append($firstClone);
    $slider.prepend($lastClone);
    
    // Update slide count
    const totalSlides = $slider.children().length;
    
    // Set width dynamically
    $slider.css({
      width: `${slideWidth * totalSlides}px`,
      display: 'flex',
      transition: 'transform 0.5s ease',
      transform: `translateX(-${slideWidth * currentIndex}px)`
    });
    
    // Slide function
    function goToSlide(index, transition = true) {
      if (transition) {
        $slider.css('transition', 'transform 0.5s ease');
      } else {
        $slider.css('transition', 'none');
      }
    
      $slider.css('transform', `translateX(-${slideWidth * index}px)`);
    }
    
    // Next slide
    function nextSlide() {
      if (isTransitioning) return;
      isTransitioning = true;
    
      currentIndex++;
      goToSlide(currentIndex);
    
      setTimeout(() => {
        if (currentIndex === totalSlides - 1) {
          currentIndex = 1;
          goToSlide(currentIndex, false);
        }
        isTransitioning = false;
      }, 500);
    }
    
    // Previous slide
    function prevSlide() {
      if (isTransitioning) return;
      isTransitioning = true;
    
      currentIndex--;
      goToSlide(currentIndex);
    
      setTimeout(() => {
        if (currentIndex === 0) {
          currentIndex = totalSlides - 2;
          goToSlide(currentIndex, false);
        }
        isTransitioning = false;
      }, 500);
    }
    
    // Events
    $('#next').click(nextSlide);
    $('#prev').click(prevSlide);
    
    // Auto Scroll
    setInterval(nextSlide, 4000);
    
    //task card carasoul
    const track = document.getElementById('carousel-track');
    const cards = Array.from(track.children);

    // Clone all cards for looping effect
    cards.forEach(card => {
        const clone = card.cloneNode(true);
        track.appendChild(clone);
    });

    let scrollX = 0;
    const scrollSpeed = 0.5; // Adjust for faster/slower scroll

    function animateScroll() {
        scrollX += scrollSpeed;
        track.style.transform = `translateX(-${scrollX}px)`;

        // Reset scroll when halfway (original + clones = 2x)
        if (scrollX >= track.scrollWidth / 2) {
            scrollX = 0;
        }

        requestAnimationFrame(animateScroll);
    }

    animateScroll();


    //scroll to top
    const scrollBtn = document.getElementById('scrollToTopBtn');

    window.addEventListener('scroll', () => {
      // Show the button when scrolled down 200px
      scrollBtn.classList.toggle('hidden', window.scrollY < 200);
    });
  
    scrollBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

});
