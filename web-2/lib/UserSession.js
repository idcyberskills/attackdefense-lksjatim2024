function UserSession(data){
  if(!(this instanceof UserSession)){
    console.log(123);
    return new UserSession(data);
  }
  var self = this;

  this.role = 'user';
  this.isAdmin = false;

  if (data) {
    return Object.keys(data).reduce(function(acc,key) {
      if(self.systemEntries.indexOf(key)===-1 && data.hasOwnProperty(key)){
        acc[key] = data[key];
      }
      return acc;
    }, this);
  }else{
    return this;
  }
}
UserSession.prototype.systemEntries = ['isAdmin','role'];

module.exports = UserSession;