import * as Config from '../config.js';

export default class Game {
    constructor(id) {
        this.id = id;
    }

    static async checkGameToken(token) {
        try {
            const result = await fetch(`${Config.serverAddress}Games/checkGameToken.php`, {
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            });
            return await result.json();
        } catch (error) {
            console.log(error);
            return error;
        }
    }

    static async saveGame(token, subID, questionCount, questions, answers) {

        try {
            const result = await fetch(`${Config.serverAddress}Games/checkAns.php`, {
                method: 'POST',
                body: JSON.stringify({
                    "Count": questionCount,
                    "SubID": subID,
                    "Questions": questions,
                    "Answers": answers
                }),
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });
            return await result.json();
        } catch (error) {
            console.log(error);
            return error;
        }

    }

}
