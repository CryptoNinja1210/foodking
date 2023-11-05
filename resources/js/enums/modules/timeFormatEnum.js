const date = new Date();
const timeFormatEnum = Object.freeze([
    {
        id: 'h:i A',
        name: '12 Hour' + ' (' + (date.getHours() > 12 ? date.getHours() % 12 : date.getHours()) + ':' + (date.getMinutes()<10?'0':'') + date.getMinutes() + ' ' + 'PM' + ')'
    },
    {
        id: 'h:i a',
        name: '12 Hour' + ' (' + (date.getHours() > 12 ? date.getHours() % 12 : date.getHours()) + ':' + (date.getMinutes()<10?'0':'') + date.getMinutes() + ' ' + 'pm' + ')'
    },
    {
        id: 'H:i',
        name: '24 Hour' + ' (' + date.getHours() + ':' + date.getMinutes() + ')'
    }

]);
export default timeFormatEnum;
