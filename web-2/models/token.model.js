module.exports = (sequelize, Sequelize) => {
    const Token = sequelize.define('tokens', {
        user_id: {
            type: Sequelize.INTEGER,
        },
        token: {
            type: Sequelize.STRING,
        },
    });
    return Token;
}
