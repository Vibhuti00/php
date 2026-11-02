/**
 * Blood Management System - Main JavaScript File
 * Handles UI interactions, mobile navigation toggles, and form validations.
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log("Blood Management System JS Loaded Successfully.");

    // 1. Mobile Navigation Menu Toggle (if hamburger menu is added)
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuToggle && navLinks) {
        mobileMenuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // 2. Dynamic Doctor Fields Toggle in Registration Form
    const roleSelect = document.getElementById('roleSelect');
    const doctorFields = document.getElementById('doctorFields');

    if (roleSelect && doctorFields) {
        roleSelect.addEventListener('change', function() {
            if (this.value === 'doctor') {
                doctorFields.style.display = 'block';
                // Make doctor inputs required
                doctorFields.querySelectorAll('input, textarea').forEach(el => {
                    if(el.name !== 'bio') el.setAttribute('required', 'true');
                });
            } else {
                doctorFields.style.display = 'none';
                doctorFields.querySelectorAll('input, textarea').forEach(el => {
                    el.removeAttribute('required');
                });
            }
        });
    }

    // 3. Client-side Form Validation for Blood Requests & Donor Registration
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const ageInput = form.querySelector('input[name="age"]');
            if (ageInput) {
                const age = parseInt(ageInput.value, 10);
                if (age < 18 || age < 0) {
                    alert('Donors must be at least 18 years of age.');
                    event.preventDefault();
                }
            }

            const unitsInput = form.querySelector('input[name="units_needed"]');
            if (unitsInput) {
                const units = parseInt(unitsInput.value, 10);
                if (units <= 0) {
                    alert('Units needed must be at least 1.');
                    event.preventDefault();
                }
            }
        });
    });

    // 4. Auto-dismiss Alert Messages after 5 seconds
    const alerts = document.querySelectorAll('.success, .error');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    }
});