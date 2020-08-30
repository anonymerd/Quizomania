import * as Config from '../config.js';

export default class Message {
    constructor(name, email, message) {
        this.name = name;
        this.email = email;
        this.message = message;
    }

    async sendMessage() {
        try {
            const result = await fetch(`${Config.serverAddress}Others/sendMail.php`, {
                method: 'POST',
                body: JSON.stringify({
                    "Name": this.name,
                    "Email": this.email,
                    "Message": this.message
                }),
            });

            return await result.json();
        } catch (error) {
            console.log(error);
            return error;
        }
    }

    static async sendResetLink(email) {
        try {
            const result = await fetch(`${Config.serverAddress}Others/sendPasswordResetLink.php`, {
                method: 'POST',
                body: JSON.stringify({
                    "Email": email
                })
            });

            return await result.json();
        } catch (error) {
            console.log(error);
            return error;
        }
    }
}