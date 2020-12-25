import * as Config from '../config.js';

export default class Leaderboard {
    static async getLeaderboard() {
        try {
            const result = await fetch(`${Config.serverAddress}Games/leaderboard.php`, {
                method: 'GET'
            });

            return await result.json();
        } catch (error) {
            return error;
        }
    }
}