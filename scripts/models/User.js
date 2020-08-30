import * as Config from '../config.js';

export default class User {
    constructor(token) {
        this.token = token;
    }

    static async logInUser(data) {
        try {
            const result = await fetch(`${Config.serverAddress}Users/logIn.php`, {
                method: 'POST',
                body: JSON.stringify(data),
            });

            return await result.json();
        } catch (error) {
            // console.log(error);
            return error;
        }
    }

    static async createUser(data) {
        try {
            const result = await fetch(`${Config.serverAddress}Users/create.php`, {
                method: 'POST',
                body: JSON.stringify(data),
            });

            return await result.json();
        } catch (error) {
            return error;
        }
    }
}
