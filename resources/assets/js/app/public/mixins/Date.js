export default {
  methods: {
    getDateTimeString: function (date) {
      date = new Date(Date.parse(date));

      let time = (date.getHours() < 10) ? `0${date.getHours()}` : date.getHours();
      time += ':';
      time += (date.getMinutes() < 10) ? `0${date.getMinutes()}` : date.getMinutes();

      return `${date.getDate()}.${date.getMonth()+1}.${date.getFullYear()} ${time}`;
    },
  }
}