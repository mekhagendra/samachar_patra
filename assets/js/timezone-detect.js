/**
 * Timezone Detection and Nepali DateTime Handler
 * Shows Nepali time for Nepal users, English time for international users
 * 
 * @package Samachar_Patra
 * @version 1.0.0
 */

(function($) {
    'use strict';

    const NepaliDateTime = {
        userTimezone: 'Asia/Kathmandu',
        isNepalUser: false,
        displayFormat: 'nepali', // 'nepali' or 'english'
        
        init: function() {
            this.detectUserLocation();
            this.setDisplayFormat();
            this.bindEvents();
            this.updateDateTimeDisplays();
            this.initLiveClock();
        },
        
        detectUserLocation: function() {
            // Try to get timezone using Intl API
            if (Intl && Intl.DateTimeFormat) {
                try {
                    this.userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                } catch (e) {
                    this.userTimezone = 'Asia/Kathmandu';
                }
            } else {
                this.userTimezone = this.getTimezoneByOffset();
            }
            
            // Determine if user is from Nepal/South Asian region
            const nepalRegionTimezones = [
                'Asia/Kathmandu'
            ];
            
            const southAsianTimezones = [
                'Asia/Kolkata',
                'Asia/Dhaka',
                'Asia/Colombo'
            ];
            
            if (nepalRegionTimezones.includes(this.userTimezone)) {
                this.isNepalUser = true;
                this.userTimezone = 'Asia/Kathmandu';
                this.displayFormat = 'nepali';
            } else if (southAsianTimezones.includes(this.userTimezone)) {
                // Close neighbors - show Nepal time but with English format
                this.isNepalUser = false;
                this.userTimezone = 'Asia/Kathmandu';
                this.displayFormat = 'english';
            } else {
                // International users - show their local time in English
                this.isNepalUser = false;
                this.displayFormat = 'english';
                // Keep their actual timezone
            }
            
            console.log('User location detected:', {
                timezone: this.userTimezone,
                isNepalUser: this.isNepalUser,
                displayFormat: this.displayFormat
            });
            
            // Update server if needed
            if (typeof nepali_datetime_ajax !== 'undefined') {
                this.updateServerTimezone();
            }
        },
        
        setDisplayFormat: function() {
            // Add class to body for CSS styling
            document.body.classList.remove('nepal-user', 'international-user');
            document.body.classList.add(this.isNepalUser ? 'nepal-user' : 'international-user');
            
            // Set data attribute for format
            document.body.setAttribute('data-datetime-format', this.displayFormat);
        },
        
        getTimezoneByOffset: function() {
            const offset = new Date().getTimezoneOffset();
            const timezoneMap = {
                '-345': 'Asia/Kathmandu',    // Nepal: UTC+5:45
                '-330': 'Asia/Kolkata',      // India: UTC+5:30
                '-360': 'Asia/Dhaka',        // Bangladesh: UTC+6:00
                '-480': 'Asia/Shanghai',     // China: UTC+8:00
                '0': 'UTC',                  // UTC
                '300': 'America/New_York',   // EST: UTC-5:00
                '420': 'America/Denver',     // MST: UTC-7:00
                '480': 'America/Los_Angeles' // PST: UTC-8:00
            };
            
            return timezoneMap[offset.toString()] || 'Asia/Kathmandu';
        },
        
        updateServerTimezone: function() {
            if (typeof nepali_datetime_ajax === 'undefined') {
                return;
            }
            
            $.ajax({
                url: nepali_datetime_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_user_timezone',
                    timezone: this.userTimezone,
                    is_nepal_user: this.isNepalUser,
                    display_format: this.displayFormat,
                    nonce: nepali_datetime_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        console.log('User location updated on server:', response.data);
                        NepaliDateTime.updateDateTimeDisplays();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to update timezone:', error);
                }
            });
        },
        
        updateDateTimeDisplays: function() {
            // Update live datetime displays
            const liveElements = document.querySelectorAll('.nepali-datetime-live');
            if (liveElements.length > 0) {
                this.updateLiveElements(liveElements);
            }
            
            // Update static elements
            const staticElements = document.querySelectorAll('.nepali-datetime-static');
            staticElements.forEach(element => {
                this.updateStaticElement(element);
            });
            
            // Update post meta times
            this.updatePostTimes();
        },
        
        initLiveClock: function() {
            // Update live clocks every minute
            setInterval(() => {
                this.updateDateTimeDisplays();
            }, 60000);
            
            // Update seconds display every second if needed
            const secondsElements = document.querySelectorAll('.nepali-time-seconds');
            if (secondsElements.length > 0) {
                setInterval(() => {
                    this.updateSecondsDisplay(secondsElements);
                }, 1000);
            }
        },
        
        updateLiveElements: function(elements) {
            const now = new Date();
            
            elements.forEach(element => {
                const format = element.getAttribute('data-format') || 'medium';
                const showTime = element.getAttribute('data-show-time') !== 'false';
                const showSeconds = element.getAttribute('data-show-seconds') === 'true';
                
                if (this.displayFormat === 'nepali' && this.isNepalUser) {
                    // Show Nepali format for Nepal users
                    element.textContent = this.formatNepalTime(now, format, showTime, showSeconds);
                    element.classList.add('nepali-format');
                    element.classList.remove('english-format');
                } else {
                    // Show English format for international users
                    element.textContent = this.formatEnglishTime(now, format, showTime, showSeconds);
                    element.classList.add('english-format');
                    element.classList.remove('nepali-format');
                }
            });
        },
        
        formatNepalTime: function(date, format, showTime, showSeconds) {
            try {
                const options = {
                    timeZone: 'Asia/Kathmandu'
                };
                
                switch (format) {
                    case 'full':
                        options.weekday = 'long';
                        options.year = 'numeric';
                        options.month = 'long';
                        options.day = 'numeric';
                        break;
                    case 'medium':
                        options.year = 'numeric';
                        options.month = 'long';
                        options.day = 'numeric';
                        break;
                    case 'short':
                    default:
                        options.month = 'short';
                        options.day = 'numeric';
                        options.year = '2-digit';
                        break;
                }
                
                if (showTime) {
                    options.hour = '2-digit';
                    options.minute = '2-digit';
                    if (showSeconds) {
                        options.second = '2-digit';
                    }
                    options.hour12 = true;
                }
                
                // Try Nepali locale first
                try {
                    const nepaliFormatter = new Intl.DateTimeFormat('ne-NP', options);
                    return nepaliFormatter.format(date);
                } catch (e) {
                    // Fallback to custom Nepali format
                    return this.customNepaliFormat(date, format, showTime);
                }
                
            } catch (error) {
                return this.customNepaliFormat(date, format, showTime);
            }
        },
        
        customNepaliFormat: function(date, format, showTime) {
            const nepaliMonths = [
                'बैशाख', 'जेठ', 'आषाढ', 'श्रावण', 'भाद्र', 'आश्विन',
                'कार्तिक', 'मंसिर', 'पौष', 'माघ', 'फाल्गुन', 'चैत्र'
            ];
            
            const nepaliDays = [
                'आइतबार', 'सोमबार', 'मंगलबार', 'बुधबार', 
                'बिहिबार', 'शुक्रबार', 'शनिबार'
            ];
            
            // Convert to Nepal timezone
            const nepalTime = new Date(date.toLocaleString("en-US", {timeZone: "Asia/Kathmandu"}));
            
            // Simple BS conversion (approximate)
            let bsYear = nepalTime.getFullYear() + 57;
            let bsMonth = nepalTime.getMonth() + 1;
            let bsDay = nepalTime.getDate();
            
            // Adjust for Nepali calendar (simplified)
            if (nepalTime.getMonth() >= 3) { // April onwards
                bsMonth = nepalTime.getMonth() - 3;
            } else {
                bsMonth = nepalTime.getMonth() + 9;
                bsYear--;
            }
            
            const dayName = nepaliDays[nepalTime.getDay()];
            const monthName = nepaliMonths[bsMonth];
            
            let result = '';
            
            switch (format) {
                case 'full':
                    result = `${dayName}, ${monthName} ${this.englishToNepaliNumbers(bsDay)}, ${this.englishToNepaliNumbers(bsYear)}`;
                    break;
                case 'medium':
                    result = `${monthName} ${this.englishToNepaliNumbers(bsDay)}, ${this.englishToNepaliNumbers(bsYear)}`;
                    break;
                case 'short':
                default:
                    result = `${monthName} ${this.englishToNepaliNumbers(bsDay)}`;
                    break;
            }
            
            if (showTime) {
                const timeStr = nepalTime.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                result += ` ${this.englishToNepaliNumbers(timeStr)}`;
            }
            
            return result;
        },
        
        formatEnglishTime: function(date, format, showTime, showSeconds) {
            const options = {
                timeZone: this.userTimezone
            };
            
            switch (format) {
                case 'full':
                    options.weekday = 'long';
                    options.year = 'numeric';
                    options.month = 'long';
                    options.day = 'numeric';
                    break;
                case 'medium':
                    options.year = 'numeric';
                    options.month = 'long';
                    options.day = 'numeric';
                    break;
                case 'short':
                default:
                    options.month = 'short';
                    options.day = 'numeric';
                    options.year = 'numeric';
                    break;
            }
            
            if (showTime) {
                options.hour = '2-digit';
                options.minute = '2-digit';
                if (showSeconds) {
                    options.second = '2-digit';
                }
                options.hour12 = true;
            }
            
            return new Intl.DateTimeFormat('en-US', options).format(date);
        },
        
        englishToNepaliNumbers: function(text) {
            const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            const nepaliNumbers = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
            
            let result = text.toString();
            for (let i = 0; i < englishNumbers.length; i++) {
                result = result.replace(new RegExp(englishNumbers[i], 'g'), nepaliNumbers[i]);
            }
            return result;
        },
        
        getRelativeTime: function(postDate) {
            const now = new Date();
            const diffInSeconds = Math.floor((now - postDate) / 1000);
            
            if (this.displayFormat === 'nepali' && this.isNepalUser) {
                return this.getRelativeTimeNepali(diffInSeconds);
            } else {
                return this.getRelativeTimeEnglish(diffInSeconds);
            }
        },
        
        getRelativeTimeNepali: function(diffInSeconds) {
            if (diffInSeconds < 60) {
                return `${this.englishToNepaliNumbers(diffInSeconds)} सेकेन्ड अगाडि`;
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${this.englishToNepaliNumbers(minutes)} मिनेट अगाडि`;
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${this.englishToNepaliNumbers(hours)} घण्टा अगाडि`;
            } else if (diffInSeconds < 2592000) {
                const days = Math.floor(diffInSeconds / 86400);
                return `${this.englishToNepaliNumbers(days)} दिन अगाडि`;
            } else if (diffInSeconds < 31536000) {
                const months = Math.floor(diffInSeconds / 2592000);
                return `${this.englishToNepaliNumbers(months)} महिना अगाडि`;
            } else {
                const years = Math.floor(diffInSeconds / 31536000);
                return `${this.englishToNepaliNumbers(years)} वर्ष अगाडि`;
            }
        },
        
        getRelativeTimeEnglish: function(diffInSeconds) {
            if (diffInSeconds < 60) {
                return `${diffInSeconds} seconds ago`;
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} minutes ago`;
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} hours ago`;
            } else if (diffInSeconds < 2592000) {
                const days = Math.floor(diffInSeconds / 86400);
                return `${days} days ago`;
            } else if (diffInSeconds < 31536000) {
                const months = Math.floor(diffInSeconds / 2592000);
                return `${months} months ago`;
            } else {
                const years = Math.floor(diffInSeconds / 31536000);
                return `${years} years ago`;
            }
        },
        
        updatePostTimes: function() {
            const postTimeElements = document.querySelectorAll('.post-time-dynamic');
            
            postTimeElements.forEach(element => {
                const postTimestamp = element.getAttribute('data-timestamp');
                if (postTimestamp) {
                    const postDate = new Date(parseInt(postTimestamp) * 1000);
                    const relativeFormat = element.getAttribute('data-relative') === 'true';
                    
                    if (relativeFormat) {
                        element.textContent = this.getRelativeTime(postDate);
                    } else {
                        const format = element.getAttribute('data-format') || 'medium';
                        const showTime = element.getAttribute('data-show-time') !== 'false';
                        
                        if (this.displayFormat === 'nepali' && this.isNepalUser) {
                            element.textContent = this.formatNepalTime(postDate, format, showTime);
                            element.classList.add('nepali-format');
                            element.classList.remove('english-format');
                        } else {
                            element.textContent = this.formatEnglishTime(postDate, format, showTime);
                            element.classList.add('english-format');
                            element.classList.remove('nepali-format');
                        }
                    }
                }
            });
        },
        
        updateSecondsDisplay: function(elements) {
            const now = new Date();
            const timeString = now.toLocaleString('en-US', {
                timeZone: 'Asia/Kathmandu',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            
            elements.forEach(element => {
                element.textContent = timeString;
            });
        },
        
        updateStaticElement: function(element) {
            const timestamp = element.getAttribute('data-timestamp');
            if (!timestamp) return;
            
            const date = new Date(parseInt(timestamp) * 1000);
            const format = element.getAttribute('data-format') || 'short';
            
            try {
                let options = { timeZone: 'Asia/Kathmandu' };
                
                switch (format) {
                    case 'full':
                        options = {
                            ...options,
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        };
                        break;
                    case 'medium':
                        options = {
                            ...options,
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        break;
                    case 'short':
                    default:
                        options = {
                            ...options,
                            month: 'short',
                            day: 'numeric'
                        };
                        break;
                }
                
                const formatter = new Intl.DateTimeFormat('ne-NP', options);
                element.textContent = formatter.format(date);
                
            } catch (error) {
                // Fallback
                element.textContent = date.toLocaleDateString('en-US', {
                    timeZone: 'Asia/Kathmandu'
                });
            }
        },
        
        bindEvents: function() {
            // Timezone selector if available
            $(document).on('change', '.timezone-selector', (e) => {
                const selectedTimezone = e.target.value;
                if (selectedTimezone && selectedTimezone !== this.userTimezone) {
                    this.userTimezone = selectedTimezone;
                    this.updateServerTimezone();
                }
            });
            
            // Manual timezone update button
            $(document).on('click', '.update-timezone-btn', (e) => {
                e.preventDefault();
                this.detectTimezone();
            });
            
            // Refresh datetime displays button
            $(document).on('click', '.refresh-datetime-btn', (e) => {
                e.preventDefault();
                this.updateDateTimeDisplays();
            });
        },
        
        // Utility function to convert timestamp to Nepal time
        convertToNepalTime: function(timestamp) {
            const date = new Date(timestamp * 1000);
            return new Intl.DateTimeFormat('en-US', {
                timeZone: 'Asia/Kathmandu',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).format(date);
        },
        
        // Get current Nepal time
        getCurrentNepalTime: function(format = 'full') {
            const now = new Date();
            const options = { timeZone: 'Asia/Kathmandu' };
            
            switch (format) {
                case 'time-only':
                    options.hour = '2-digit';
                    options.minute = '2-digit';
                    options.hour12 = true;
                    break;
                case 'date-only':
                    options.year = 'numeric';
                    options.month = 'long';
                    options.day = 'numeric';
                    break;
                case 'full':
                default:
                    options.weekday = 'long';
                    options.year = 'numeric';
                    options.month = 'long';
                    options.day = 'numeric';
                    options.hour = '2-digit';
                    options.minute = '2-digit';
                    options.hour12 = true;
                    break;
            }
            
            try {
                return new Intl.DateTimeFormat('ne-NP', options).format(now);
            } catch (e) {
                return new Intl.DateTimeFormat('en-US', options).format(now);
            }
        },
        
        // Format relative time
        getRelativeTime: function(timestamp) {
            const now = Date.now() / 1000;
            const diff = now - timestamp;
            
            if (diff < 60) return 'अहिले';
            if (diff < 3600) return Math.floor(diff / 60) + ' मिनेट पहिले';
            if (diff < 86400) return Math.floor(diff / 3600) + ' घण्टा पहिले';
            if (diff < 604800) return Math.floor(diff / 86400) + ' दिन पहिले';
            
            // Return formatted date for older posts
            return this.convertToNepalTime(timestamp);
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        NepaliDateTime.init();
        
        // Make it globally accessible
        window.NepaliDateTime = NepaliDateTime;
        
        // Auto-update any elements with 'auto-update' class
        $('.nepali-datetime-auto').each(function() {
            const $this = $(this);
            const interval = parseInt($this.data('interval')) || 60000;
            
            setInterval(() => {
                NepaliDateTime.updateDateTimeDisplays();
            }, interval);
        });
    });
    
    // Handle AJAX content loading
    $(document).ajaxComplete(function() {
        // Update datetime displays in newly loaded content
        setTimeout(() => {
            NepaliDateTime.updateDateTimeDisplays();
        }, 100);
    });
    
})(jQuery);

// Standalone functions for non-jQuery environments
window.NepaliDateTimeUtils = {
    getCurrentNepalTime: function() {
        const now = new Date();
        return now.toLocaleString('en-US', {
            timeZone: 'Asia/Kathmandu',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    },
    
    formatTimestamp: function(timestamp) {
        const date = new Date(timestamp * 1000);
        return date.toLocaleString('en-US', {
            timeZone: 'Asia/Kathmandu',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }
};

