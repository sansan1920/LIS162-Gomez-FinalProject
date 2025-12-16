import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const formatTime = (timeString) => {
    const [hours, minutes] = timeString.split(':');
    const date = new Date(2000, 0, 1, hours, minutes);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
};


window.reservationForm = () => ({
    selectedDate: '',
    selectedFacilityId: 0,
    selectedTimeslotId: '',
    selectedDayName: '',

    allSchedules: [], 
    uniqueFacilities: [], 
    reservationStatus: {},

    filteredSchedules: [],

    formatTime(timeString) {
        return formatTime(timeString);
    },

    initializeForm(schedules, facilities, reservationStatus) {
        this.allSchedules = schedules;
        this.uniqueFacilities = facilities;
        this.reservationStatus = reservationStatus;
    },

    filterSchedules() {
        this.filteredSchedules = [];
        this.selectedTimeslotId = ''; 

        let dayName = '';
        if (this.selectedDate) {
            const selectElement = document.getElementById('reservation_date');
            const selectedOption = Array.from(selectElement.options).find(opt => opt.value === this.selectedDate);
            dayName = selectedOption ? selectedOption.getAttribute('data-day-name') : '';
            this.selectedDayName = dayName;
        }

        if (!this.selectedDate || this.selectedFacilityId === 0) return;

        const facilityId = Number(this.selectedFacilityId);

        this.filteredSchedules = this.allSchedules.filter(schedule => {
            const isDayMatch = schedule.day_of_week === dayName;
            
            const isFacilityMatch = schedule.facility_id === facilityId;
            
            const reservationKey = `${this.selectedDate}_${schedule.schedule_id}`;
            const isReserved = !!this.reservationStatus[reservationKey]; 

            return isDayMatch && isFacilityMatch && !isReserved; 
        });
    },

    isFormValid() {
        return this.selectedDate && this.selectedFacilityId != 0 && this.selectedTimeslotId;
    }
});

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start(); 
});