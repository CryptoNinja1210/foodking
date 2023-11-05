const date = new Date();
const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const fullMonthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];
const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const dateFormatEnum = Object.freeze([
    {
        id: 'd-m-Y',
        name: 'd-m-Y' + ' (' + ('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear() + ')'
    },
    {
        id: 'm-d-Y',
        name: 'm-d-Y' + ' (' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2) + '-' + date.getFullYear() + ')'
    },
    {
        id: 'Y-m-d',
        name: 'Y-m-d' + ' (' + date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2) + ')'
    },
    {
        id: 'd.m.Y',
        name: 'd.m.Y' + ' (' + ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + date.getFullYear() + ')'
    },
    {
        id: 'm.d.Y',
        name: 'm.d.Y' + ' (' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + ('0' + date.getDate()).slice(-2) + '.' + date.getFullYear() + ')'
    },
    {
        id: 'Y.m.d',
        name: 'Y.m.d' + ' (' + date.getFullYear() + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + ('0' + date.getDate()).slice(-2) + ')'
    },
    {
        id: 'd/m/Y',
        name: 'd/m/Y' + ' (' + ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear() + ')'
    },
    {
        id: 'm/d/Y',
        name: 'm/d/Y' + ' (' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + ('0' + date.getDate()).slice(-2) + '/' + date.getFullYear() + ')'
    },
    {
        id: 'Y/m/d',
        name: 'Y/m/d' + ' (' + date.getFullYear() + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + ('0' + date.getDate()).slice(-2) + ')'
    },
    {
        id: 'd-M-Y',
        name: 'd-M-Y' + ' (' + ('0' + date.getDate()).slice(-2) + '-' + monthNames[date.getMonth()] + '-' + date.getFullYear() + ')'
    },
    {
        id: 'd/M/Y',
        name: 'd/M/Y' + ' (' + ('0' + date.getDate()).slice(-2) + '/' + monthNames[date.getMonth()] + '/' + date.getFullYear() + ')'
    },
    {
        id: 'd.M.Y',
        name: 'd.M.Y' + ' (' + ('0' + date.getDate()).slice(-2) + '.' + monthNames[date.getMonth()] + '.' + date.getFullYear() + ')'
    },
    {
        id: 'd M Y',
        name: 'd M Y' + ' (' + ('0' + date.getDate()).slice(-2) + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear() + ')'
    },
    {
        id: 'd F, Y',
        name: 'd F, Y' + ' (' + ('0' + date.getDate()).slice(-2) + ' ' + fullMonthNames[date.getMonth()] + ', ' + date.getFullYear() + ')'
    },
    {
        id: 'd D M Y',
        name: 'd D M Y' + ' (' + ('0' + date.getDate()).slice(-2) + ' ' + days[date.getDay()] + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear() + ')'
    },
    {
        id: 'D d M Y',
        name: 'D d M Y' + ' (' + days[date.getDay()] + ' ' + ('0' + date.getDate()).slice(-2) + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear() + ')'
    },
    {
        id: 'dS M Y ',
        name: 'dS M Y ' + ' (' + '1st' + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear() + ')'
    }
]);


export default dateFormatEnum;


