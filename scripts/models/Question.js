import * as Config from '../config.js';

export default class Question {
    constructor(id) {
        this.id = id;
    }

    async getQuestion(token) {
        try {
            const result = await fetch(`${Config.serverAddress}Questions/read.php?id=${this.id}`, {
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

    static async getRandomQuestions(subID, count, token) {
        try {
            const result = await fetch(`${Config.serverAddress}Questions/randomQues.php`, {
                method: 'POST',
                body: JSON.stringify({
                    Count: count,
                    SubID: subID,
                }),
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

    static async updateAnswer(token, answer) {

        try {
            const result = await fetch(`${Config.serverAddress}Questions/updateAnswer.php`, {
                method: 'PUT',
                body: JSON.stringify({
                    'Answer': answer
                }),
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
}
